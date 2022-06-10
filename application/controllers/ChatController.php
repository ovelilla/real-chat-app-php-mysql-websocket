<?php

namespace Controllers;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

use Models\User;
use Models\GeneralMessage;

class ChatController implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;

        echo "Server Started\n";
    }

    public function onOpen(ConnectionInterface $conn): void {
        $this->clients->attach($conn);

        // $querystring = $conn->httpRequest->getUri()->getQuery();

        // parse_str($querystring, $queryarray);

        // if (isset($queryarray['token'])) {
        //     $user = new User();

        //     $user->setToken($queryarray['token']);
        //     $user->setConnectionId($conn->resourceId);

        //     foreach ($this->clients as $client) {
        //         $client->send(json_encode($queryarray['token']));
        //     }
        // }

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg): void {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n", $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg, true);

        $message = new GeneralMessage($data);
        $message->save();

        $user = new User(['id' => $data['id_user']]);
        $user = $user->where(['id' => $user->getId()]);

        $data['from'] = $user['name'];
        $data['date'] = $message->getDate();
        
        foreach ($this->clients as $client) {
            if ($from === $client) {
                $data['type'] = 'sent';
            } else {
                $data['type'] = 'reply';
            }

            $client->send(json_encode($data));
        }
    }

    public function onClose(ConnectionInterface $conn): void {
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    public static function read() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = [
                'status' => 'error',
                'msg' => 'Bad request method'
            ];
            echo json_encode($response);
            return;
        }

        $message = new GeneralMessage();
        $messages = $message->getAll();

        $response = [
            'status' => 'success',
            'messages' => $messages
        ];

        echo json_encode($response);
    }
}

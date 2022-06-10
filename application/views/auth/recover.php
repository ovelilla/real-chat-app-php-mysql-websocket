<main>
    <h1 class="title mt-10">Realtime Chat App using <span class="text-indigo-600">Websockets</span></h1>

    <h2 class="subtitle">Recupera tu Cuenta</h2>

    <form id="recover-form" class="auth-form" novalidate>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Tu email">
        </div>

        <div class="field actions">
            <a href="/registrar">Crea cuenta</a>
            <a href="/recuperar">¿Olvidaste la contraseña?</a>
        </div>

        <div class="field buttons">
            <button type="submit" id="recover-form-btn" class="btn primary-btn">Recuperar</button>
        </div>
    </form>
</main>
<main>
    <h1 class="title mt-10">Realtime Chat App using <span class="text-indigo-600">Websockets</span></h1>

    <h2 class="subtitle">Crea una nueva cuenta</h2>

    <form id="signup-form" class="auth-form" novalidate>
        <div class="field">
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" placeholder="Tu nombre">
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Tu email">
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" name="password_current" id="password" placeholder="Tu Password" />
        </div>

        <div class="field">
            <label for="password_repeat">Repetir password</label>
            <input type="password" name="password_repeat" id="password_repeat" placeholder="Repetir Password" />
        </div>

        <div class="field actions">
            <a href="/">Iniciar sesión</a>
            <a href="/recuperar">¿Olvidaste la contraseña?</a>
        </div>

        <div class="field buttons">
            <button type="submit" id="signup-form-btn" class="btn primary-btn">Registrarse</button>
        </div>
    </form>
    </div>
</main>
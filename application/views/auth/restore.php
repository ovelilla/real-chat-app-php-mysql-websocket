<main>
    <h1 class="title mt-10">Realtime Chat App using <span class="text-indigo-600">Websockets</span></h1>

    <h2 class="subtitle">Crea una nueva contraseña</h2>

    <form id="restore-form" class="auth-form" novalidate>
        <div class="field">
            <label for="password">Password nuevo</label>
            <input type="password" name="password_current" id="password" placeholder="Tu Password" />
        </div>

        <div class="field actions">
            <a href="/registrar">Crea cuenta</a>
            <a href="/recuperar">¿Olvidaste la contraseña?</a>
        </div>

        <div class="field buttons">
            <button type="submit" id="restore-form-btn" class="btn primary-btn">Restablecer</button>
        </div>
    </form>
</main>
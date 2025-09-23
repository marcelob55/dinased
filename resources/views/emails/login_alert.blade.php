<!doctype html>
<html lang="es"><body>
  <p>Hola {{ $user->nombres ?? $user->nickname ?? 'usuario' }},</p>
  <p>Se registró un inicio de sesión en tu cuenta.</p>
  <ul>
    <li>Fecha/hora: {{ $when->format('Y-m-d H:i:s') }}</li>
    <li>IP: {{ $ip }}</li>
    <li>Dispositivo: {{ $ua }}</li>
  </ul>
  <p>Si no fuiste tú, cambia tu contraseña de inmediato.</p>
</body></html>

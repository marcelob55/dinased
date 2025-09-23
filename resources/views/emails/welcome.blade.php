<!doctype html>
<html lang="es"><body>
  <p>Hola {{ $user->nombres ?? $user->nickname ?? 'usuario' }},</p>
  <p>Tu registro se realizó correctamente. Por favor verifica tu correo desde el mensaje
  que acabamos de enviarte (si no lo ves, revisa Spam).</p>
  <p>Agencia: {{ $user->agencia ?? '—' }} | Equipo: {{ $user->equipo ?? '—' }}</p>
  <p>Gracias.</p>
</body></html>

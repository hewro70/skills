<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <body style="font-family:Arial,Helvetica,sans-serif;line-height:1.7;color:#0f172a;background:#f8fafc;padding:32px;">
    @php
      $invitation->loadMissing(['sourceUser','destinationUser']);

      $brand   = config('app.name', 'Maharat Hub');
      $appUrl  = config('app.url') ?: url('/');

      $sender   = $invitation->sourceUser?->fullName() ?: ($invitation->sourceUser->email ?? 'Sender');
      $receiver = $invitation->destinationUser?->fullName() ?: ($invitation->destinationUser->email ?? 'User');

      $tr = function (string $key, string $fallback, array $params = []) {
        $t = __($key, $params);
        return ($t === $key) ? $fallback : $t;
      };
    @endphp

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:680px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 10px 24px rgba(2,6,23,.06);">
      <tr>
        <td style="padding:26px 28px;">
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
            <img src="{{ $appUrl }}/img/logo.png" alt="{{ $brand }}" width="28" height="28" style="display:block;border-radius:6px;">
            <div style="font-weight:700;letter-spacing:.2px;">{{ $brand }}</div>
          </div>

          <h2 style="margin:0 0 10px;font-size:20px;">
            {{ $tr('mail.invitation.created.title', "You've received a new invitation") }}
          </h2>

          <p style="margin:0 0 14px;">
            {{ $tr('mail.invitation.created.body', "Hello :receiver, :sender has sent you an invitation on :brand.", ['receiver'=>$receiver,'sender'=>$sender,'brand'=>$brand]) }}
          </p>

          @if(filled($invitation->message))
            <div style="margin:16px 0;padding:12px 14px;background:#f1f5f9;border-radius:10px;">
              <strong style="display:block;margin-bottom:6px;">{{ $tr('mail.invitation.message', 'Message') }}</strong>
              <div style="white-space:pre-line">{{ $invitation->message }}</div>
            </div>
          @endif

          <div style="margin:18px 0 6px;">
            <a href="{{ $appUrl }}"
               style="display:inline-block;padding:10px 16px;border-radius:10px;background:#0284c7;color:#fff;text-decoration:none;font-weight:600;">
              {{ $tr('mail.invitation.view_site', 'Go to website') }}
            </a>
          </div>

          <p style="margin:16px 0 0;color:#64748b;font-size:12px;">
            {{ $tr('mail.invitation.footer', 'Thanks for using :brand.', ['brand'=>$brand]) }}
          </p>
        </td>
      </tr>
    </table>
  </body>
</html>

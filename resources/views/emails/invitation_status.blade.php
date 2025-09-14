<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <body style="font-family:Arial,Helvetica,sans-serif;line-height:1.7;color:#0f172a;background:#f8fafc;padding:32px;">
    @php
      $invitation->loadMissing(['sourceUser','destinationUser']);

      $for   = $for  ?? 'sender'; // 'sender' | 'receiver'
      $norm  = $invitation->reply_norm ?? 'pending'; // accepted | rejected | pending

      $brand  = config('app.name', 'Maharat Hub');
      $appUrl = config('app.url') ?: url('/');

      $sender   = $invitation->sourceUser?->fullName() ?: ($invitation->sourceUser->email ?? __('mail.user'));
      $receiver = $invitation->destinationUser?->fullName() ?: ($invitation->destinationUser->email ?? __('mail.user'));

      // ترجمة مع فولباك
      $tr = function (string $key, string $fallback, array $params = []) {
        $t = __($key, $params);
        return ($t === $key) ? $fallback : $t;
      };
    @endphp

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:680px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 10px 24px rgba(2,6,23,.06);">
      <tr>
        <td style="padding:26px 28px;">
          {{-- Header --}}
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
            <img src="{{ $appUrl }}/img/logo.png" alt="{{ $brand }}" width="28" height="28" style="display:block;border-radius:6px;">
            <div style="font-weight:700;letter-spacing:.2px;">{{ $brand }}</div>
          </div>

          {{-- Title --}}
          <h2 style="margin:0 0 10px;font-size:20px;">
            @if($for === 'sender')
              {{ $norm === 'accepted'
                   ? $tr('mail.invitation.sender.accepted_title', 'Your invitation was accepted 🎉')
                   : $tr('mail.invitation.sender.rejected_title', 'Your invitation was rejected') }}
            @else
              {{ $norm === 'accepted'
                   ? $tr('mail.invitation.receiver.accepted_title', 'You accepted the invitation')
                   : $tr('mail.invitation.receiver.rejected_title', 'You rejected the invitation') }}
            @endif
          </h2>

          {{-- Body --}}
          <p style="margin:0 0 14px;">
            @if($for === 'sender')
              {{ $norm === 'accepted'
                   ? $tr('mail.invitation.sender.accepted_body', "Good news! {$receiver} has accepted your invitation.", ['receiver' => $receiver])
                   : $tr('mail.invitation.sender.rejected_body', "{$receiver} has rejected your invitation.", ['receiver' => $receiver]) }}
            @else
              {{ $norm === 'accepted'
                   ? $tr('mail.invitation.receiver.accepted_body', "You accepted {$sender}'s invitation.", ['sender' => $sender])
                   : $tr('mail.invitation.receiver.rejected_body', "You rejected {$sender}'s invitation.", ['sender' => $sender]) }}
            @endif
          </p>

          @if(filled($invitation->message))
            <div style="margin:16px 0;padding:12px 14px;background:#f1f5f9;border-radius:10px;">
              <strong style="display:block;margin-bottom:6px;">{{ $tr('mail.invitation.message', 'Message') }}</strong>
              <div style="white-space:pre-line">{{ $invitation->message }}</div>
            </div>
          @endif

          {{-- CTAs --}}
          <div style="margin:18px 0 6px;display:flex;gap:10px;flex-wrap:wrap;">
            @if($norm === 'accepted' && $for === 'sender')
              <a href="{{ url('/conversations') }}"
                 style="display:inline-block;padding:10px 16px;border-radius:10px;background:#0284c7;color:#fff;text-decoration:none;font-weight:600;">
                {{ $tr('mail.invitation.open_chat', 'Open conversation') }}
              </a>
            @endif

            <a href="{{ $appUrl }}"
               style="display:inline-block;padding:10px 16px;border-radius:10px;background:#f8fafc;color:#0f172a;text-decoration:none;border:1px solid #e5e7eb;">
              {{ $tr('mail.invitation.visit_site', 'Visit website') }}
            </a>
          </div>

          {{-- Footer (بدون أي ID/تاريخ) --}}
          <p style="margin:16px 0 0;color:#64748b;font-size:12px;">
            {{ $tr('mail.invitation.footer', 'Thanks for using :brand.', ['brand'=>$brand]) }}
          </p>
        </td>
      </tr>
    </table>
  </body>
</html>

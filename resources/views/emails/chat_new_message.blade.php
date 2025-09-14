<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <body style="font-family:Arial,Helvetica,sans-serif;line-height:1.6;color:#0f172a;background:#f8fafc;padding:24px;">
    @php
      $brand  = config('app.name','Maharat Hub');
      $appUrl = config('app.url') ?: url('/');
      $sender = $chat->user?->fullName() ?: ($chat->user?->email ?? 'User');
    @endphp

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:0 auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;">
      <tr>
        <td style="padding:20px 22px;">
          <h2 style="margin:0 0 10px;font-size:18px;">
            {{ __('mail.chat.new_message_title') ?? 'You have a new message' }}
          </h2>

          <p style="margin:0 0 12px;">
            {{ __('mail.chat.new_message_body', ['sender'=>$sender, 'brand'=>$brand]) 
                ?? "You received a new message from {$sender} on {$brand}." }}
          </p>

          <blockquote style="margin:12px 0;padding:10px 12px;background:#f1f5f9;border-radius:10px;">
            {{ \Illuminate\Support\Str::limit($chat->body, 140) }}
          </blockquote>

          <div style="margin-top:14px;">
            <a href="{{ url('/conversations/'.$conversation->id) }}"
               style="display:inline-block;padding:8px 14px;border-radius:8px;background:#0ea5e9;color:#fff;text-decoration:none;">
              {{ __('mail.chat.open_conversation') ?? 'Open conversation' }}
            </a>
          </div>
        </td>
      </tr>
    </table>
  </body>
</html>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Новая заявка</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #111827;">
    <h1 style="font-size: 20px; margin-bottom: 16px;">Новая заявка в Larding CMS</h1>

    <p><strong>Форма:</strong> {{ $lead->name }}</p>
    <p><strong>Статус:</strong> {{ $lead->status }}</p>
    <p><strong>Дата:</strong> {{ $lead->created_at?->format('d.m.Y H:i:s') }}</p>

    @if ($lead->content)
        <div style="margin-top: 20px;">
            <strong>Содержимое заявки:</strong>
            <pre style="white-space: pre-wrap; background: #f3f4f6; padding: 12px; border-radius: 8px;">{{ $lead->content }}</pre>
        </div>
    @endif
</body>
</html>

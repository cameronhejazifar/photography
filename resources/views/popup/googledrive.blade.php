<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script type="text/javascript">
        @if($oauth)
        window.opener.closePopupWindow(window, {
            success: true,
            oauth: { expires_at: {{ $oauth->expires_at->getPreciseTimestamp(3) }} }
        });
        @else
        window.opener.closePopupWindow(window, { success: false, oauth: null });
        @endif
    </script>
</head>
<body>
</body>
</html>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DataSource test</title>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
    </head>
    <body>
        <label for="picklist">{{$title}}</label>
        <select id="picklist">
            @foreach ($options as $option)
                <option title="{{ $option["title"] }}" value="{{ $option["value"] }}">{{ $option["name"] }}</option>
            @endforeach
        </select>
    </body>

    <script type="text/javascript">
        $("#picklist").tooltip();
    </script>
</html>

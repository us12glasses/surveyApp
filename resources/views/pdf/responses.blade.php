<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #ddd; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h2>Survey Responses Report</h2>
    <table>
        <thead>
            <tr>
                <th>Question</th>
                <th>Answer</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($responses as $response)
            <tr>
                <td>{{ $response['question'] }}</td>
                <td>{{ $response['answer'] }}</td>
                <td>{{ $response['date'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
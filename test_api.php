$request = \Illuminate\Http\Request::create('/api/games/146', 'GET');

$request = Request::create('/api/games/146', 'GET');
$response = app()->handle($request);

echo "Status Code: " . $response->getStatusCode() . "\n";
echo "Content: \n" . $response->getContent() . "\n";

# ChatGPT Api in PHP

Eine minimalistische und leichtgewichtige API für die Integration von ChatGPT in PHP-Projekten.

Diese API wurde speziell entwickelt, um den Einstieg so einfach wie möglich zu gestalten, ohne dabei viel Speicherplatz oder komplizierte Abhängigkeiten zu benötigen. Sie ist ideal für Entwickler, die eine unkomplizierte Möglichkeit suchen, ChatGPT-Funktionen in ihre PHP-Anwendungen zu integrieren.

## Beispiele
Einfacher Textrequest:
```
$api = new \ChatGPT\API('sandbox-user-123456ABCDEFG123456ABCDEFG...');
$response = $api->complexeRequest('Du bist Harry Potter','Was macht deine Figur aus?');
print_r($response);
```

Umfangreicher Textrequest:
```
$api = new \ChatGPT\API('sandbox-user-123456ABCDEFG123456ABCDEFG...');

$thread = $api->createThread();
$thread->setAssistantId('asst_123...');
$thread->addTextMessage('Du bist ein Namenerfinder und gibst immer 5 Vorschläge.',\ChatGPT\Role::ASSISTANT);
$thread->addTextMessage('Wie könnte mein Fisch heissen?',\ChatGPT\Role::USER);
$thread->run()->wait();
print_r($thread->getLastMessage());
```

Vorhandene Assistenten abfragen:
```
$api = new \ChatGPT\API('sandbox-user-123456ABCDEFG123456ABCDEFG...');

foreach ($api->getAssistants() as $assistant)
    print_r($assistant->name);
```

Name eines Assistenten ändern:
```
$api = new \ChatGPT\API('sandbox-user-123456ABCDEFG123456ABCDEFG...');

$assistant = $api->getAssistantById('asst_123...');

$assistant->name = 'Neuer Name';
$assistant->save();
```


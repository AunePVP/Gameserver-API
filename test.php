<?php

function sendSourceQuery($host, $port, $queryType)
{
    $socket = @fsockopen("udp://" . $host, $port, $errno, $errstr, 1);

    if (!$socket) {
        die("Error: $errno - $errstr");
    }

    // Build and send the Source Query packet
    $data = "\xFF\xFF\xFF\xFF$queryType\x00";
    fwrite($socket, $data);

    // Read the response
    $response = fread($socket, 4096);
    fclose($socket);

    // Print the raw response
    echo "Raw Response for $queryType:\n";
    echo bin2hex($response) . "\n\n";

    return $response;
}

function getA2SInfo($host, $port)
{
    sendSourceQuery($host, $port, "\x54Source Engine Query\x00");
}

function getA2SPlayer($host, $port)
{
    return sendSourceQuery($host, $port, "\x55Source Engine Query\x00");
}

function getA2SRules($host, $port)
{
    return sendSourceQuery($host, $port, "\x56Source Engine Query\x00");
}

// Replace with the actual IP address and port of your Source Query server
$serverHost = '12.34.56.78';
$serverPort = 27015;

// Get A2S_INFO
getA2SInfo($serverHost, $serverPort);

// Get A2S_PLAYER
$playerResponse = getA2SPlayer($serverHost, $serverPort);
echo "A2S_PLAYER Response:\n$playerResponse\n\n";

// Get A2S_RULES
$rulesResponse = getA2SRules($serverHost, $serverPort);
echo "A2S_RULES Response:\n$rulesResponse\n\n";
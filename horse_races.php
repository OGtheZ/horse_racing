<?php



$wallet = (int) readline("How much money are you ready to bet? ");
if (!is_numeric($wallet)) {
    echo "Use only numbers when adding money to the wallet!";
    exit;
}
$gameIsLive = true;

while ($gameIsLive === true) {
    $trackLength = 15;
    $runningTrack = array_fill(0, $trackLength, "_");
    $stadium = [];
    $playerCount = 5;
    $players = ["X", "@", "&", "#", "$"];

    $multiplier = [rand(1, 5), rand(1, 5), rand(2, 5), rand(3, 6), rand(1, 8)];

    echo "The horses on the track today are: " . PHP_EOL;
    foreach ($players as $key => $horse) {
        echo "$horse with multiplier $multiplier[$key]. " . PHP_EOL;
    }
    echo PHP_EOL;

    $playerChoices = [];
    $bets = [];
    $choosing = true;
    while ($choosing) {
        echo "You have $wallet $ left!" . PHP_EOL;
        $playerChoice = readline("Choose your horse: ");
        if (!in_array($playerChoice, $players)) {
            echo "Invalid horse! " . PHP_EOL;
            continue;
        }
        $playerChoices[] = $playerChoice;
        $bet = (int)readline("Enter bet : ");
        if ($bet > $wallet) {
            echo "Not enough funds!" . PHP_EOL;
            continue;
        }
        $wallet = $wallet - $bet;
        $bets[] = $bet;
        $continue = readline("Choose another horse? y/n  ");
        if ($continue === 'n') {
            $choosing = false;
        } else {
            $choosing = true;
        }
    }

    for ($i = 0; $i < $playerCount; $i++) {
        array_push($stadium, $runningTrack);
        $stadium[$i][0] = $players[$i];
    }

    $theRaceIsLive = true;
    $podium = [];

    while ($theRaceIsLive) {
        foreach ($stadium as $runner) {
            echo implode(" ", $runner) . PHP_EOL;
            echo "!-!-!-!-!-!-!-!-!-!-!-!-!-!" . PHP_EOL;
        }

        for ($i = 0; $i < $playerCount; $i++) {
            $position = array_search($players[$i], $stadium[$i]);
            if ($position < count($stadium[$i]) - 1) {
                $stadium[$i][$position] = "_";
                $stadium[$i][$position + rand(1, 2)] = $players[$i];
            } else {
                $podium[] = $players[$i];
                $podium = array_unique($podium);
            }
            if (count($podium) === $playerCount) {
                $theRaceIsLive = false;
                $podium = array_unique($podium);
                for ($i = 1; $i < $playerCount + 1; $i++) {
                    echo "$i." . $podium[$i - 1] . PHP_EOL;
                }
            }
        }
        sleep(1);
    }

    if (in_array($podium[0], $playerChoices)) {
        echo "The horse $podium[0] has won!" . PHP_EOL;
        $multiplier = $multiplier[array_search($podium[0], $players)];
        $total = $bets[array_search($podium[0], $playerChoices)] * $multiplier;
        echo "You won " . $total . " $!" . PHP_EOL;
        $wallet = $wallet + $total;
        echo "You have $wallet $!";
    } else {
        echo "Your horse did not win! Better luck next time!" . PHP_EOL;
    }
    if ($wallet === 0) {
        echo "You lost all the funds!" . PHP_EOL;
        exit;
    }

    $playAgain = readline("Would you like to play again?  y/n  ");

    if ($playAgain === 'n') {
        $gameIsLive = false;
        echo "Thank you for playing!" . PHP_EOL;
    }
}

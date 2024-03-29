<?php

declare(strict_types=1);

$emails = [
    'john.doe@gmail.com',
    'jane.smith@yahoo.com',
    'bob.johnson@hotmail.com',
    'mary.wilson@outlook.com',
    'david.brown@aol.com',
    'susan.williams@icloud.com',
    'kevin.miller@protonmail.com',
    'linda.anderson@live.com',
    'michael.clark@mail.com',
    'emily.parker@gmx.com',
    'steve.adams@gmail.com',
    'laura.evans@yahoo.com',
    'peter.white@hotmail.com',
    'sarah.jackson@outlook.com',
    'ryan.wright@aol.com',
    'elizabeth.thomas@icloud.com',
    'james.roberts@protonmail.com',
    'lisa.harris@live.com',
    'matthew.hall@mail.com',
    'jennifer.moore@gmx.com',
    'robert.lee@gmail.com',
    'angela.green@yahoo.com',
    'william.wilson@hotmail.com',
    'michelle.brown@outlook.com',
    'richard.martin@aol.com',
    'patricia.davis@icloud.com',
    'charles.murphy@protonmail.com',
    'cynthia.parker@live.com',
    'joseph.cook@mail.com',
    'amanda.robertson@gmx.com',
    'thomas.stewart@gmail.com',
    'rebecca.turner@yahoo.com',
    'daniel.rogers@hotmail.com',
    'linda.edwards@outlook.com',
    'christopher.price@aol.com',
    'karen.hill@icloud.com',
    'mark.carter@protonmail.com',
    'lisa.bailey@live.com',
    'joseph.wood@mail.com',
    'susan.cooper@gmx.com',
    'david.morris@gmail.com',
    'jennifer.ross@yahoo.com',
    'william.ward@hotmail.com',
    'sandra.richardson@outlook.com',
    'michael.foster@aol.com',
    'sarah.kelly@icloud.com',
    'james.mitchell@protonmail.com',
    'linda.myers@live.com',
    'robert.reed@mail.com',
    'jennifer.howard@gmx.com',
    'john.watson@gmail.com',
    'laura.wood@yahoo.com',
    'david.bell@hotmail.com',
    'susan.cooper@outlook.com',
    'kevin.perry@aol.com',
    'elizabeth.adams@icloud.com',
    'james.hall@protonmail.com',
    'karen.thompson@live.com',
    'william.green@mail.com',
    'mary.roberts@gmx.com',
    'david.martin@gmail.com',
    'linda.walker@yahoo.com',
    'robert.hill@hotmail.com',
    'jennifer.lewis@outlook.com',
    'michael.young@aol.com',
    'susan.miller@icloud.com',
    'james.davis@protonmail.com',
    'laura.moore@live.com',
    'robert.johnson@mail.com',
    'elizabeth.harris@gmx.com',
    'john.martin@gmail.com',
    'karen.wilson@yahoo.com',
    'david.anderson@hotmail.com',
    'susan.parker@outlook.com',
    'kevin.white@aol.com',
    'mary.martinez@icloud.com',
    'michael.johnson@protonmail.com',
    'linda.brown@live.com',
    'robert.moore@mail.com',
    'elizabeth.thompson@gmx.com',
    'john.walker@gmail.com',
    'jennifer.lewis@yahoo.com',
    'david.hall@hotmail.com',
    'susan.roberts@outlook.com',
    'kevin.martin@aol.com',
    'mary.jones@icloud.com',
    'james.wilson@protonmail.com',
    'linda.clark@live.com',
    'robert.davis@mail.com',
    'elizabeth.lewis@gmx.com',
    'john.smith@gmail.com',
    'jennifer.brown@yahoo.com',
    'david.miller@hotmail.com',
    'susan.adams@outlook.com',
    'kevin.harris@aol.com',
    'mary.davis@icloud.com',
    'michael.wilson@protonmail.com',
    'linda.thomas@live.com',
    'robert.wright@mail.com',
];

function getNotPassedTest(array $passedTests): int {
    $testId = random_int(1, 10);

    return in_array($testId, $passedTests, true) ? getNotPassedTest($passedTests) : $testId;
}

$getRandomAnswerId = fn (int $questionId): int => ($questionId * 4) - random_int(0, 3);

$scores = [];

foreach ($emails as $email) {
    $neededTestsCountToPass = random_int(1, 10);
    $currentTestCounter = 1;
    $passedTests = [];

    while ($currentTestCounter <= $neededTestsCountToPass) {
        $testId = getNotPassedTest($passedTests);
        $passedTests[] = $testId;

        for ($questionId = (($testId * 10) - 10 + 1); $questionId <= ($testId * 10); $questionId++) {
            $answerId = $getRandomAnswerId($questionId);

            $scores[\App\Domain\Score::class]["score_for_{$email}_with_question_{$questionId}"] = [
                'email' => $email,
                'test_id' => $testId,
                'question_id' => $questionId,
                'answer_id' => $answerId,
                'points' => "@answer_{$answerId}->points",
            ];
        }

        $currentTestCounter++;
    }
}

return $scores;

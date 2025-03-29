<?php

use PawSalon\Presentation\Controller\Pet;

return [
    ['GET', '/pets', [Pet::class, 'list']],
    ['POST', '/pets', [Pet::class, 'register']],
];

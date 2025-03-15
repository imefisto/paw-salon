<?php

use PawSalon\Presentation\Controller\Pet;

return [
    ['POST', '/pets', [Pet::class, 'register']],
];

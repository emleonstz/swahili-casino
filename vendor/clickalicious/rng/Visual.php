<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Rng.
 *
 * Visual.php - Visualization of random numbers generated by this lib.
 *
 *
 * PHP versions 5.3
 *
 * LICENSE:
 * Rng - Random number generator for PHP
 *
 * Copyright (c) 2015, Benjamin Carl
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * - Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer.
 *
 * - Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 *
 * - Neither the name of Rng nor the names of its
 * contributors may be used to endorse or promote products derived from
 * this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * Please feel free to contact us via e-mail: opensource@clickalicious.de
 *
 * @category  Clickalicious
 *
 * @author    Benjamin Carl <opensource@clickalicious.de>
 * @copyright 2015 Benjamin Carl
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 *
 * @version   Git: $Id$
 *
 * @link      https://github.com/clickalicious/Rng
 */
require_once 'src/Clickalicious/Rng/Bootstrap.php';

// Clean config
define('RNG_VISUAL_ITERATIONS', 100000);


// We generate a visual representation/demo of randomness for this implementations (PNG)
header('Content-type: image/png');

// Dimension of one tile
$width  = 800;
$height = 800;

// Our generators
$generators = [
    new Clickalicious\Rng\Generator(\Clickalicious\Rng\Generator::MODE_PHP_DEFAULT),
    new Clickalicious\Rng\Generator(\Clickalicious\Rng\Generator::MODE_PHP_MERSENNE_TWISTER),
    new Clickalicious\Rng\Generator(\Clickalicious\Rng\Generator::MODE_MCRYPT),
    new Clickalicious\Rng\Generator(\Clickalicious\Rng\Generator::MODE_OPEN_SSL),
];

$countGenerators = count($generators);

// Complete width for all generators
$totalWidth = $countGenerators * $width;
$img        = imagecreatetruecolor(count($generators) * $width, $height);

imagefilledrectangle($img, 0, 0, $totalWidth, $height, imagecolorallocate($img, 255, 255, 255));

// Iterate and draw
for ($i = 0; $i < $countGenerators; ++$i) {
    $color = imagecolorallocate($img, 0, 0, 0);
    $p     = 0;

    for ($j = 0; $j < RNG_VISUAL_ITERATIONS; ++$j) {
        $np = $generators[$i]->generate(0, $width);
        imagesetpixel($img, $p + ($width * $i), $np, $color);
        $p = $np;
    }
}

imagepng($img);
imagedestroy($img);

<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Clickalicious\Rng;

/**
 * Rng.
 *
 * Bootstrap.php - Bootstrapping of directories and so on.
 *
 *
 * PHP versions 5.4
 *
 * LICENSE:
 * Rng - Plain vanilla PHP Rng client with full support of Rng protocol.
 *
 * Copyright (c) 2015 - 2016, Benjamin Carl
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
 * @copyright 2015 - 2016 Benjamin Carl
 * @license   https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 *
 * @version   Git: $Id$
 *
 * @link      https://github.com/clickalicious/Rng
 */
require_once 'Autoloader.php';

/**
 * Detects composer in global scope.
 *
 * @author Benjamin Carl <opensource@clickalicious.de>
 *
 * @return bool TRUE if composer is active, otherwise FALSE
 */
function composer_running()
{
    $result  = false;
    $classes = get_declared_classes();
    natsort($classes);
    foreach ($classes as $class) {
        if (stristr($class, 'ComposerAutoloaderInit')) {
            $result = true;
            break;
        }
    }

    return $result;
}


// The base path to /src/ if we don't have Composer we need to know root path
define(
    'CLICKALICIOUS_RNG_BASE_PATH',
    realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR).
    DIRECTORY_SEPARATOR
);

// Root node
$root = realpath(CLICKALICIOUS_RNG_BASE_PATH.'../');

// Check for composer existence
if (true === $composerExist = $composerRunning = file_exists($root.'/vendor/autoload.php')) {
    include_once $root.'/vendor/autoload.php';
} else {
    $composerExist = $composerRunning = composer_running();
}

// No need to double detect and so on ...
define(
'CLICKALICIOUS_RNG_COMPOSER_EXISTS',
$composerExist
);

define(
'CLICKALICIOUS_RNG_COMPOSER_RUNNING',
$composerRunning
);

// Force reporting of all errors ...
error_reporting(-1);

// Init autoloading
$loader = new Autoloader();

// register the autoloader
$loader->register();

// register the base directories for the namespace prefix
$loader->addNamespace('Clickalicious\Rng', CLICKALICIOUS_RNG_BASE_PATH.'Clickalicious\Rng');

<?php

/**
 * RouletteWheel class.
 *
 * RouletteWheel: utility used to pick up a delivery server using probability
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://mailixa.net/).
 *
 * @category   MVC Model
 *
 * @author     N. Pham <n.pham@mailixa.net>
 * @author     L. Pham <l.pham@mailixa.net>
 * @copyright  Acelle Co., Ltd.
 * @license    Acelle Co., Ltd.
 *
 * @version    1.0
 *
 * @link       http://mailixa.net
 */

namespace Acelle\Library;

class RouletteWheel
{
    // pick a random element from an array based on its probability
    public static function generate($a)
    {
        $sum = 0.0;
        $total = array_sum(array_values($a));
        $r = self::frandom();

        // just in case
        if ($r == $sum) { // in other words, r == 0
            return array_keys($a)[0];
        }

        foreach ($a as $key => $percentage) {
            $newsum = $sum + (float) $percentage / (float) $total;
            if ($r > $sum && $r <= $newsum) {
                return $key;
            }
            $sum = $newsum;
        }

        // just in case
        return array_keys($a)[sizeof($a) - 1];
    }

    public static function frandom()
    {
        return (float) rand() / (float) getrandmax();
    }
}

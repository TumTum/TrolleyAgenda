<?php
/**
 * Created for TrolleyAgenda
 *
 * @author: Tobias Matthaiou <matthaiou@tobimat.eu>
 * @copyright: 2016 Tobias Matthaiou
 */

namespace %namespace%;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \%subject%
 */
class %name% extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('%subject%');
    }
}

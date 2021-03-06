<?php declare(strict_types=1);
/*
 * This file is part of sebastian/diff.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\Diff\Output;

/**
 * Builds a diff string representation in a loose unified diff format
 * listing only changes lines. Does not include line numbers.
 */
final class DiffOnlyOutputBuilder implements DiffOutputBuilderInterface
{
    /**
     * @var string
     */
    private $header;

    public function __construct(string $header = "--- Original\n+++ New\n")
    {
        $this->header = $header;
    }

    public function getDiff(array $diff): string
    {
        $buffer = \fopen('php://memory', 'r+b');
        \fwrite($buffer, $this->header);

        foreach ($diff as $diffEntry) {
            if ($diffEntry[1] === 1 /* ADDED */) {
                \fwrite($buffer, '+' . $diffEntry[0] . "\n");
            } elseif ($diffEntry[1] === 2 /* REMOVED */) {
                \fwrite($buffer, '-' . $diffEntry[0] . "\n");
            }
        }

        $diff = \stream_get_contents($buffer, -1, 0);
        \fclose($buffer);

        return $diff;
    }
}

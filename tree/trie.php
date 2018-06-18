<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2018-06
 */

function make(array $words) {
    $nodes = [
        [false, []],    // 根节点
    ];

    $count = 1;

    foreach ($words as $word) {
        $current = 0;   // 重置至根节点

        for ($i = 0; $i < strlen($word); ++$i) {
            $alpha = $word{$i};
            if (isset($nodes[$current][1][$alpha])) {
                $current = $nodes[$current][1][$alpha];
                continue;
            }

            $nodes[$current][1][$alpha] = $count;
            $nodes[$count] = [false, []];
            $current = $count;
            ++$count;
        }

        $nodes[$current][0] = true;
    }

    return $nodes;
}

function search($needle, array $words)
{
    $nodes = make($words);

    $return = [];

    $current = 0;

    // 回溯位置, 相当于跟屁虫, 在未有匹配时, i 走到哪里, p 就跟到哪里.
    // 而当有匹配时, 停止跟屁虫, 标记为开始匹配的位置.
    // 当匹配结束有产生匹配结果时, 用于截取出字符串. 截取后, 把 p 设置为 i + 1, 以防止重复执行执行
    $p = 0;

    for ($i = 0; $i < strlen($needle); ++$i) {
        $alpha = $needle{$i};
        if (!isset($nodes[$current][1][$alpha])) {
            $current = 0;
            $i = $p;
            $p = $i + 1;
            continue;
        }

        $current = $nodes[$current][1][$alpha];

        if (true === $nodes[$current][0]) {
            $return[] = substr($needle, $p, $i - $p + 1);

            // 如果被标注为叶子节点, 但实际并非叶子节点, 则跳过回溯
            if (0 === count($nodes[$current][1])) {
                $p = $i + 1;
                $current = 0;
            }
        }
    }

    return $return;
}

$arr = search('I am runnerlee', ['runner', 'runnerlee']);

print_r($arr);
<?php

/**
 * Возвращает список переходов из локации $from_id в локацию $area_id
 *
 * @param $area_id int
 * @param $from_id int
 *
 * @return array
 */

function area_links_list($area_id, $from_id) {
    // Например здесь может быть запрос вида SELECT * FROM `area_links` WHERE `from_id` = (int) $from_id AND `area_id` = (int) $area_id AND `active` = 1

    return [];
}

/**
 * Осуществляет переход пользователя в новую локацию
 *
 * @param $user array Объект пользователя
 * @param $area array Объект зоны назначения
 *
 * @throws
 */

function come_in( &$user, $area ) {
    // Проверяем параметры на то, что передали действующее лицо
    if( !$user ) {
        throw new \InvalidArgumentException("Пользователь не определён");
    }

    // Проверяем параметра на то, что передали локацию назначения
    if( !$area ) {
        throw new \InvalidArgumentException("Локация не определена");
    }

    if( $user['area_freeze_until'] < time() ) {
        throw new \Exception("Вы не можете передвигаться");
    }

    // Проверяем что мы уже не находимся в
    if( $user['area_id'] == $area['id'] ) {
        throw new \Exception("Вы уже находитесь в этой локации");
    }

    $links = area_links_list($area['id'], $user['area_id']);

    if( count($links) === 0 ) {
        throw new \Exception("Переход не найден");
    }

    if( $area['restriction_id'] ) {
        restriction_check($user, $area['restriction_id']);
    }

    $user['area_id'] = $area['id'];

    if( $area['freeze_time'] > 0 ) {
        $user['area_freeze_until'] = time() + $area['freeze_time'];
    }

    // Не забываем обновить пользователя в базе данных
    // UPDATE `users` SET `area_id` = (int) $area['id'], `area_freeze_until` = (int) $user['area_freeze_until'] WHERE `id` = (int) $user['id']
}
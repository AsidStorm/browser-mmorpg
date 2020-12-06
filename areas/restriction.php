<?php

/**
 * Проверяет ограничение для пользователя
 *
 * @param $user array Пользователь, для которого требуется проверить ограничение
 * @param $restriction_id int Идентификатор ограничения
 * @throws Exception
 */

function restriction_check($user, $restriction_id) {
    if( !$user ) {
        throw new \InvalidArgumentException("Пользователь для проверки ограничения не определён");
    }

    if( !$restriction_id ) {
        throw new \InvalidArgumentException("Ограничение не определено");
    }

    $restriction = restriction_get($restriction_id);
    $items = restriction_items_list($restriction['id']);

    foreach( $items as $item ) {
        switch($item['type']) {
            case 'LEVEL':
                if( $user['level'] < $item['value'] ) {
                    throw new \Exception("Ваш уровень [" . $user['level'] . "] меньше необходимого [" . $item['value'] . "]");
                }

                break;
        }
    }
}

/**
 * Возвращает ограничение по идентификатору
 *
 * @param $restriction_id
 * @return array
 */

function restriction_get($restriction_id) {
    return [];
}

/**
 * Возвращает набор условий для ограничения
 *
 * @param $restriction_id int Идентификатор ограничения
 * @return array
 */

function restriction_items_list($restriction_id) {
    return [];
}
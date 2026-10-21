<?php
    function format_vk_link ($vk_club)
    {
        $vk_club = trim ($vk_club);
        return "https://vk.ru/$vk_club";
    }

    function format_lj_link ($lj_comm)
    {
        $lj_comm = trim ($lj_comm);
        return "http://$lj_comm.livejournal.com/profile";
    }

    function format_fb_link ($fb_comm)
    {
        $fb_comm = trim ($fb_comm);
        return "https://www.facebook.com/groups/$fb_comm/";
    }

    function format_telegram_link($handle)
    {
        $handle = trim($handle);
        return "https://t.me/$handle";
    }

    // Ссылку без схемы (www.example.ru) браузер считает относительной и приклеивает
    // к текущему пути — так у ботов получались бесконечные URL (issue #114)
    function format_site_link($uri)
    {
        $uri = trim($uri);
        if ($uri === '' || preg_match('#^([a-z][a-z0-9+.-]*://|mailto:|/)#i', $uri))
        {
            return $uri;
        }
        return "http://$uri";
    }

    function normalize_link($link)
    {
        static $prestfixes = array (
        'http://', 'https://',
        'vk.com/', 'vk.ru/', 'vkontakte.ru/',
        '.livejournal.com', '.lj.ru',
        'facebook.com/groups/',
        't.me/', '@',
        '/');
        return str_replace ($prestfixes, '', $link);
    }
?>
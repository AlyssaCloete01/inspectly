<?php

/**
 * This class is used as an enumerator for API supported resource types. Since php
 * Does not have an enum structure, we using classes to achieve the goal.
 * 
 * @author Themba Malungan <themba@kazazoom.com>
 * @version 1.0.0
 * @since 2015/05/18
 */
class APIResourceType{
    const POSTS     = 'posts';
    const POST      = 'post';
    const QUIZZES   = 'quizzes';
    const QUIZ      = 'quiz';
    const FORMS     = 'forms';
    const FORM      = 'form';
    const POST_CATEGORY = 'post_category';
    const POST_CATEGORIES = 'post_categories';
    const MENU      = 'menu';
    const MENUS     = 'menus';
    const FRONT_PAGE = 'front_page';
}


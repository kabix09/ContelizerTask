<?php

enum ApiHeadersEnum: string
{
    case X_LINKS_CURRENT = 'x-links-current';
    case X_LINKS_NEXT = 'x-links-next';
    case X_LINKS_PREVIOUS = 'x-links-previous';
    case X_PAGINATION_LIMIT = 'x-pagination-limit';
    case X_PAGINATION_PAGE = 'x-pagination-page';
    case X_PAGINATION_PAGES = 'x-pagination-pages';
    case X_PAGINATION_TOTAL = 'x-pagination-total';
}
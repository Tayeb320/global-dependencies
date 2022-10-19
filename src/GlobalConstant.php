<?php

namespace Itclanbd\GlobalServiceDependencies;


class GlobalConstant
{
    // Const
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    const VALIDATION_ERROR_MESSAGE = 'Validation Error';
    const HTTP_STATUS_CODE_NOT_FOUND = 404;
    const HTTP_STATUS_CODE_UNPROCESSABLE_ENTITY = 422;

    const DEFAULT_TEXT_DIRECTION = 'ltr';

    const ATTRIBUTE_TYPE_TEXT = 'text';
    const ATTRIBUTE_TYPE_COLOR = 'color';
    const ATTRIBUTE_TYPE_IMAGE = 'image';

    const ATTRIBUTE_TYPES = [
        self::ATTRIBUTE_TYPE_TEXT => 'Text',
        self::ATTRIBUTE_TYPE_COLOR => 'Color',
        self::ATTRIBUTE_TYPE_IMAGE => 'Image',
    ];
}
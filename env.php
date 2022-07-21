<?php

$variables = [
	'AUTHOR_POSTS_PAGINATE_COUNT' => 9999999,  // تعداد پست در هر صفحه آرشیو پست های نویسنده
	'DEFAULT_DOLLAR_EXCHANGE_RATE' => 25000,
	'DEFAULT_YUAN_EXCHANGE_RATE' => 3900
];

foreach ($variables as $key => $value) {
	putenv("$key=$value");
}
<?php

$variables = [
	'AUTHOR_POSTS_PAGINATE_COUNT' => 5  // تعداد پست در هر صفحه آرشیو پست های نویسنده
];

foreach ($variables as $key => $value) {
	putenv("$key=$value");
}
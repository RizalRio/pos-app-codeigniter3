<?php
if (!function_exists('generate_captcha')) {
	function generate_captcha()
	{
		$CI = get_instance();
		$vals = [
			// 'word' -> nantinya akan digunakan sebagai random teks yang akan keluar di captchanya
			'word' => substr(str_shuffle('0123456789'), 0, 4),
			'img_path' => './assets/images/captcha/',
			'img_url' => base_url('assets/images/captcha/'),
			'img_width' => 280,
			'img_height' => 40,
			'expiration' => 7200,
			'word_length' => 4,
			'font_path'     => './assets/font/arialblack.ttf',
			'font_size' => 20,
			'img_id' => 'Imageid',
			'pool' => '0123456789',
			'colors' => [
				'background' => [255, 255, 255],
				'border' => [255, 255, 255],
				'text' => [0, 0, 0],
				'grid' => [255, 40, 40]
			]
		];

		$captcha = create_captcha($vals);

		$CI->session->set_userdata('captcha', $captcha['word']);

		return $captcha['image'];
	}
}

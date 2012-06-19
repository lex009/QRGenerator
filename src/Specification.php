<?php
namespace id009\QRGenerator;

/**
 * Class for keeping specification data
 * 
 * @@author AlexBelyaev <a.v.belyaev@gmail.com>
 */
class Specification
{
	/**
	 * Allowed data modes
	 * @var array
	 */
	public static $allowedDatamodes = array('Alphanumeric', 'Binary');
	
	/**
	 * Allowed error correction
	 * @var array
	 */
	public static $allowedErrorCorrection = array('L', 'M', 'Q', 'H');
	
	/**
	 * Maximal length of data per version, error correction level and data mode
	 * @var array
	 */
	public static $maxLength = array(
		1 => array(
			'L' => array(
				'Numeric' => 41,
				'Alphanumeric' => 25,
				'Binary' => 17,
			),
			'M' => array(
				'Numeric' => 34,
				'Alphanumeric' => 20,
				'Binary' => 14,
			),
			'Q' => array(
				'Numeric' => 27,
				'Alphanumeric' => 16,
				'Binary' => 11,
			),
			'H' => array(
				'Numeric' => 17,
				'Alphanumeric' => 10,
				'Binary' => 7,
			),
		),
		
		2 => array(
			'L' => array(
				'Numeric' => 77,
				'Alphanumeric' => 47,
				'Binary' => 32,
			),
			'M' => array(
				'Numeric' => 63,
				'Alphanumeric' => 38,
				'Binary' => 26,
			),
			'Q' => array(
				'Numeric' => 48,
				'Alphanumeric' => 29,
				'Binary' => 20,
			),
			'H' => array(
				'Numeric' => 34,
				'Alphanumeric' => 20,
				'Binary' => 14,
			),
		),
		
		3 => array(
			'L' => array(
				'Numeric' => 127,
				'Alphanumeric' => 77,
				'Binary' => 53,
			),
			'M' => array(
				'Numeric' => 101,
				'Alphanumeric' => 61,
				'Binary' => 42,
			),
			'Q' => array(
				'Numeric' => 77,
				'Alphanumeric' => 47,
				'Binary' => 32,
			),
			'H' => array(
				'Numeric' => 58,
				'Alphanumeric' => 35,
				'Binary' => 24,
			),
		),
		
		4 => array(
			'L' => array(
				'Numeric' => 187,
				'Alphanumeric' => 114,
				'Binary' => 78,
			),
			'M' => array(
				'Numeric' => 149,
				'Alphanumeric' => 90,
				'Binary' => 62,
			),
			'Q' => array(
				'Numeric' => 111,
				'Alphanumeric' => 67,
				'Binary' => 46,
			),
			'H' => array(
				'Numeric' => 82,
				'Alphanumeric' => 50,
				'Binary' => 34,
			),
		),
			
		5 => array(
			'L' => array(
				'Numeric' => 255,
				'Alphanumeric' => 154,
				'Binary' => 106,
			),
			'M' => array(
				'Numeric' => 202,
				'Alphanumeric' => 122,
				'Binary' => 84,
			),
			'Q' => array(
				'Numeric' => 144,
				'Alphanumeric' => 87,
				'Binary' => 60,
			),
			'H' => array(
				'Numeric' => 106,
				'Alphanumeric' => 64,
				'Binary' => 44,
			),	
		),
		
		6 => array(
			'L' => array(
				'Numeric' => 322,
				'Alphanumeric' => 195,
				'Binary' => 134,
			),
			'M' => array(
				'Numeric' => 255,
				'Alphanumeric' => 154,
				'Binary' => 106,
			),
			'Q' => array(
				'Numeric' => 178,
				'Alphanumeric' => 108,
				'Binary' => 74,
			),
			'H' => array(
				'Numeric' => 139,
				'Alphanumeric' => 84,
				'Binary' => 58,
			),	
		),
		
		7 => array(
			'L' => array(
				'Numeric' => 370,
				'Alphanumeric' => 224,
				'Binary' => 154,
			),
			'M' => array(
				'Numeric' => 293,
				'Alphanumeric' => 178,
				'Binary' => 122,
			),
			'Q' => array(
				'Numeric' => 207,
				'Alphanumeric' => 125,
				'Binary' => 86,
			),
			'H' => array(
				'Numeric' => 154,
				'Alphanumeric' => 93,
				'Binary' => 64,
			),	
		),
		
		8 => array(
			'L' => array(
				'Numeric' => 461,
				'Alphanumeric' => 279,
				'Binary' => 192,
			),
			'M' => array(
				'Numeric' => 365,
				'Alphanumeric' => 221,
				'Binary' => 152,
			),
			'Q' => array(
				'Numeric' => 259,
				'Alphanumeric' => 157,
				'Binary' => 108,
			),
			'H' => array(
				'Numeric' => 202,
				'Alphanumeric' => 122,
				'Binary' => 84,
			),	
		),
		
		9 => array(
			'L' => array(
				'Numeric' => 552,
				'Alphanumeric' => 335,
				'Binary' => 230,
			),
			'M' => array(
				'Numeric' => 432,
				'Alphanumeric' => 262,
				'Binary' => 180,
			),
			'Q' => array(
				'Numeric' => 312,
				'Alphanumeric' => 189,
				'Binary' => 130,
			),
			'H' => array(
				'Numeric' => 235,
				'Alphanumeric' => 143,
				'Binary' => 98,
			),	
		),
		
		10 => array(
			'L' => array(
				'Numeric' => 652,
				'Alphanumeric' => 395,
				'Binary' => 217,
			),
			'M' => array(
				'Numeric' => 513,
				'Alphanumeric' => 311,
				'Binary' => 213,
			),
			'Q' => array(
				'Numeric' => 364,
				'Alphanumeric' => 221,
				'Binary' => 151,
			),
			'H' => array(
				'Numeric' => 288,
				'Alphanumeric' => 174,
				'Binary' => 119,
			),	
		),
	);
	
	/**
	 * @var array Count of bits to keep information of data length
	 */
	public static $dataLengthInBits = array(
		'1-9' => array(
			'Numeric' => 10,
			'Alphanumeric' => 9,
			'Binary' => 8,
			'Japanese' => 8,
		),
		
		'10-26' => array(
			'Numeric' => 12,
			'Alphanumeric' => 11,
			'Binary' => 16,
			'Japanese' => 10,
		),
		
		'27-40' => array(
			'Numeric' => 14,
			'Alphanumeric' => 13,
			'Binary' => 16,
			'Japanese' => 12,
		),
		 
	);
	
	/**
	 * @var array Information about Reed-Solomon block for code version and error correction level
	 */
	public static $RSBlocks = array(
		1 => array(
			'L' => array(1, 26, 19),
			'M' => array(1, 26, 16),
			'Q' => array(1, 26, 13),
			'H' => array(1, 26, 9),
		),
		
		2 => array(
			'L' => array(1, 44, 34),
			'M' => array(1, 44, 28),
			'Q' => array(1, 44, 22),
			'H' => array(1, 44, 16),
		),
		
		3 => array(
			'L' => array(1, 70, 55),
			'M' => array(1, 70, 44),
			'Q' => array(2, 35, 17),
			'H' => array(2, 35, 13),
		),
		
		4 => array(
			'L' => array(1, 100, 80),
			'M' => array(2, 50, 32),
			'Q' => array(2, 50, 24),
			'H' => array(4, 25, 9),
		),
		
		5 => array(
			'L' => array(1, 134, 108),
			'M' => array(2, 67, 43),
			'Q' => array(2, 33, 15, 2, 34,16),
			'H' => array(2, 33, 11, 2, 34, 12),
		),
		
		6 => array(
			'L' => array(2, 86, 68),
			'M' => array(4, 43, 27),
			'Q' => array(4, 43, 19),
			'H' => array(4, 43, 15),
		),
		
		7 => array(
			'L' => array(2, 98, 78),
			'M' => array(4, 49, 31),
			'Q' => array(2, 32, 14, 4, 33, 15),
			'H' => array(4, 39, 13, 1, 40, 14),
		),
		
		8 => array(
			'L' => array(2, 121, 97),
			'M' => array(2, 60, 38, 2, 61, 39),
			'Q' => array(4, 40, 18, 2, 41, 19),
			'H' => array(4, 40, 14, 2, 41, 15),
		),
		
		9 => array(
			'L' => array(2, 146, 116),
			'M' => array(3, 58, 36, 2, 59, 37),
			'Q' => array(4, 36, 16, 4, 37, 17),
			'H' => array(4, 36, 12, 4, 37, 13),
		),
		
		10 => array(
			'L' => array(2, 86, 68, 2, 87, 69),
			'M' => array(4, 69, 43, 1, 70, 44),
			'Q' => array(6, 43, 19, 2, 44, 20),
			'H' => array(6, 43, 15, 2, 44, 16),
		),
		
	);
	
	/**
	 * @var array Bits used to encode code version
	 */
	public static $versionBits  = array(
		7  => '001010010011111000',
		8  => '000111101101000100',
		9  => '100110010101100100',
		10 => '110010110010010010',
		11 => '011011111101110100',
		12 => '001000110111001100',
		13 => '111000100001101100',
		14 => '010110000011011100',
		15 => '000101001001111100',
		16 => '000111101101000010',
		17 => '010111010001100010',
		18 => '111010000101010010',
		19 => '001001100101110010',
		20 => '011001011001001010',
		21 => '011000001011101010',
		22 => '100100110001011010',
		23 => '000110111111111010',
		24 => '001000110111000110',
		25 => '000100001111100110',
		26 => '110101011111010110',
		27 => '000001110001110110',
		28 => '010110000011001110',
		29 => '001111110011101110',
		30 => '101011101011011110',
		31 => '000000101001111110',
		32 => '101010111001000001',
		33 => '000001111011100001',
		34 => '010111010001010001',
		35 => '011111001111110001',
		36 => '110100001101001001',
		37 => '001110100001101001',
		38 => '001001100101011001',
		39 => '010000010101111001',
		40 => '100101100011000101',
	);
	
	
	/**
	 * @var array Information about position adjustments
	 */
	public static $positionAdjustments = array(
		1  => array(),
		2  => array(6, 18),
		3  => array(6, 22),
		4  => array(6, 26),
		5  => array(6, 30),
		6  => array(6, 34),
		7  => array(6, 22, 38),
		8  => array(6, 24, 42),
		9  => array(6, 26, 46),
		10 => array(6, 28, 50),
		11 => array(6, 30, 54),		
		12 => array(6, 32, 58),
		13 => array(6, 34, 62),
		14 => array(6, 26, 46, 66),
		15 => array(6, 26, 48, 70),
		16 => array(6, 26, 50, 74),
		17 => array(6, 30, 54, 78),
		18 => array(6, 30, 56, 82),
		19 => array(6, 30, 58, 86),
		20 => array(6, 34, 62, 90),
		21 => array(6, 28, 50, 72, 94),
		22 => array(6, 26, 50, 74, 98),
		23 => array(6, 30, 54, 78, 102),
		24 => array(6, 28, 54, 80, 106),
		25 => array(6, 32, 58, 84, 110),
		26 => array(6, 30, 58, 86, 114),
		27 => array(6, 34, 62, 90, 118),
		28 => array(6, 26, 50, 74, 98, 122),
		29 => array(6, 30, 54, 78, 102, 126),
		30 => array(6, 26, 52, 78, 104, 130),
		31 => array(6, 30, 56, 82, 108, 134),
		32 => array(6, 34, 60, 86, 112, 138),
		33 => array(6, 30, 58, 86, 114, 142),
		34 => array(6, 34, 62, 90, 118, 146),
		35 => array(6, 30, 54, 78, 102, 126, 150),
		36 => array(6, 24, 50, 76, 102, 128, 154),
		37 => array(6, 28, 54, 80, 106, 132, 158),
		38 => array(6, 32, 58, 84, 110, 136, 162),
		39 => array(6, 26, 54, 82, 110, 138, 166),
		40 => array(6, 30, 58, 86, 114, 142, 170),
	);
	
	/**
	 * @var Bits used to encode error correction level and mask
	 */
	public static $typeBits = array(
		'L' => array(
			0 => '111011111000100',
			1 => '111001011110011',
			2 => '111110110101010',
			3 => '111100010011101',
			4 => '110011000101111',
			5 => '110001100011000',
			6 => '110110001000001',
			7 => '110100101110110',
		),
		
		'M' => array(
			0 => '101010000010010',
			1 => '101000100100101',
			2 => '101111001111100',
			3 => '101101101001011',
			4 => '100010111111001',
			5 => '100000011001110',
			6 => '100111110010111',
			7 => '100101010100000',
		),
		
		'Q' => array(
			0 => '011010101011111',
			1 => '011000001101000',
			2 => '011111100110001',
			3 => '011101000000110',
			4 => '010010010110100',
			5 => '010000110000011',
			6 => '010111011011010',
			7 => '010101111101101',
		),
		
		'H' => array(
			0 => '001011010001001',
			1 => '001001110111110',
			2 => '001110011100111',
			3 => '001100111010000',
			4 => '000011101100010',
			5 => '000001001010101',
			6 => '000110100001100',
			7 => '000100000111011',
		),
	);
	
	/**
	 * Returns mask function by its number
	 * 
	 * @param int $maskPattern
	 * @return function
	 */
	public static function getMaskPattern($maskPattern){
		$maskPatterns = array(
			'0' => function ($x, $y) {return ($x + $y) % 2 == 0;},
			'1' => function ($x, $y) {return $x % 2 == 0;},
			'2' => function ($x, $y) {return $y % 3 == 0;},
			'3' => function ($x, $y) {return ($x + $y) % 3 == 0;},
			'4' => function ($x, $y) {return (floor($x / 2) + floor($y / 3)) % 2 == 0;},
			'5' => function ($x, $y) {return ($x * $y) % 2 + ($x * $y) % 3 == 0;},
			'6' => function ($x, $y) {return (($x * $y) % 2 + ($x * $y) % 3) % 2 == 0;},
			'7' => function ($x, $y) {return (($x * $y) % 3 + ($x + $y) % 2) % 2 == 0;}
		);
		
		return $maskPatterns[$maskPattern];
	}
} 

?>
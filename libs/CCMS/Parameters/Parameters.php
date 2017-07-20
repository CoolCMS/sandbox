<?php

namespace CCMS\Parameters;


class Parameters
{
	const DATETIME_FORMAT = 'Y-m-d H:i:s';
	const DATE_REGEXP = '#^\d\d\d\d-\d\d-\d\d\z#';
	const DATETIME_REGEXP = '#^\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d\z#';

	/**
	 * options
	 */
	const REQUIRED = 1;

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @var string|null
	 */
	private $domain;


	/**
	 * @param array $data
	 * @return Parameters
	 */
	public static function from(array $data)
	{
		return new Parameters($data);
	}


	/**
	 * @param array $data
	 * @param string|null $domain
	 */
	private function __construct(array $data, $domain = null)
	{
		$this->data = $data;
		$this->domain = $domain;
	}


	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasKey($key)
	{
		return array_key_exists($key, $this->data);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return string
	 */
	public function getString($key, $options = 0)
	{
		return $this->get($key, 'string', false, $options, '', null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return string|null
	 */
	public function getStringOrNull($key, $options = 0)
	{
		return $this->get($key, 'string', true, $options, null, null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return int
	 */
	public function getInt($key, $options = 0)
	{
		return $this->get($key, 'int', false, $options, 0, null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return int|null
	 */
	public function getIntOrNull($key, $options = 0)
	{
		return $this->get($key, 'int', true, $options, null, null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return float
	 */
	public function getFloat($key, $options = 0)
	{
		return $this->get($key, 'float', false, $options, 0.0, null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return float|null
	 */
	public function getFloatOrNull($key, $options = 0)
	{
		return $this->get($key, 'float', true, $options, null, null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return bool
	 */
	public function getBool($key, $options = 0)
	{
		return $this->get($key, 'bool', false, $options, false, null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return bool|null
	 */
	public function getBoolOrNull($key, $options = 0)
	{
		return $this->get($key, 'bool', true, $options, null, null);
	}


	/**
	 * @param $key
	 * @param int $options
	 * @return \DateTime
	 */
	public function getDate($key, $options = 0)
	{
		return $this->get($key, 'date', false, $options, new \DateTime('0000-01-01 00:00:00'), null);
	}


	/**
	 * @param $key
	 * @param int $options
	 * @return \DateTime
	 */
	public function getDateOrNull($key, $options = 0)
	{
		return $this->get($key, 'date', true, $options, null, null);
	}


	/**
	 * @param $key
	 * @param int $options
	 * @return \DateTime
	 */
	public function getDateTime($key, $options = 0)
	{
		return $this->get($key, 'datetime', false, $options, new \DateTime('0000-01-01 00:00:00'), null);
	}


	/**
	 * @param $key
	 * @param int $options
	 * @return \DateTime
	 */
	public function getDateTimeOrNull($key, $options = 0)
	{
		return $this->get($key, 'datetime', true, $options, null, null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return array
	 */
	public function getArray($key, $options = 0)
	{
		return $this->get($key, 'array', false, $options, [], null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return array|null
	 */
	public function getArrayOrNull($key, $options = 0)
	{
		return $this->get($key, 'array', true, $options, null, null);
	}


	/**
	 * @param string $key
	 * @param string $type
	 * @param int $options
	 * @return object
	 */
	public function getObject($key, $type, $options = 0)
	{
		if (!($options & static::REQUIRED)) {
			throw new InvalidArgumentException('Option Parameters::REQUIRED is required.');
		}

		return $this->get($key, 'object', false, $options, null, $type);
	}


	/**
	 * @param string $key
	 * @param string $type
	 * @param int $options
	 * @return object|null
	 */
	public function getObjectOrNull($key, $type, $options = 0)
	{
		return $this->get($key, 'object', true, $options, null, $type);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return Parameters
	 */
	public function getParameters($key, $options = 0)
	{
		$array = $this->get($key, 'array', false, $options, [], null);
		return new static($array, $this->prefixKey($key));
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return Parameters|null
	 */
	public function getParametersOrNull($key, $options = 0)
	{
		$array = $this->get($key, 'array', true, $options, null, null);
		return $array !== null ? new static($array, $this->prefixKey($key)) : null;
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return string[]
	 */
	public function getStringList($key, $options = 0)
	{
		return $this->getList($key, 'string', false, $options, null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return int[]
	 */
	public function getIntList($key, $options = 0)
	{
		return $this->getList($key, 'int', false, $options, null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return float[]
	 */
	public function getFloatList($key, $options = 0)
	{
		return $this->getList($key, 'float', false, $options, null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return bool[]
	 */
	public function getBoolList($key, $options = 0)
	{
		return $this->getList($key, 'bool', false, $options, null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return \DateTime[]
	 */
	public function getDateList($key, $options = 0)
	{
		return $this->getList($key, 'date', false, $options, null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return \DateTime[]
	 */
	public function getDateTimeList($key, $options = 0)
	{
		return $this->getList($key, 'datetime', false, $options, null);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return array[]
	 */
	public function getArrayList($key, $options = 0)
	{
		return $this->getList($key, 'array', false, $options, null);
	}


	/**
	 * @param string $key
	 * @param string $type
	 * @param int $options
	 * @return object[]
	 */
	public function getObjectList($key, $type, $options = 0)
	{
		return $this->getList($key, 'object', false, $options, $type);
	}


	/**
	 * @param string $key
	 * @param int $options
	 * @return Parameters[]
	 */
	public function getParametersList($key, $options = 0)
	{
		$list = $this->getList($key, 'array', false, $options, null);

		foreach ($list as $k => &$value) {
			$value = new static($value, $this->prefixKey($key) . '[' . $k . ']');
		}

		return $list;
	}

	/**
	 * @param string $key
	 * @param string $type
	 * @param bool $nullable
	 * @param int $options
	 * @param mixed $defaultValue
	 * @param string|null $objectType
	 * @return mixed
	 */
	private function get($key, $type, $nullable, $options, $defaultValue, $objectType)
	{
		if (!$this->hasKey($key)) {
			if ($options & static::REQUIRED) {
				throw new InvalidParameterException('Parameter ' . $this->prefixKey($key) . ' is required.');
			} else {
				$value = $defaultValue;
			}
		} else {
			$value = $this->data[$key];
		}

		if (!$this->checkType($value, $type, $nullable, $objectType)) {
			throw new InvalidParameterException('Parameter ' . $this->prefixKey($key) . ' must be '
				. $this->formatTypeLabel($type, $nullable, $objectType) . '.');
		}

		return $value;
	}

	/**
	 * @param string $key
	 * @param string $type
	 * @param bool $nullable
	 * @param int $options
	 * @param string|null $objectType
	 * @return array
	 */
	private function getList($key, $type, $nullable, $options, $objectType)
	{
		$list = $this->get($key, 'list', false, $options, [], null);

		foreach ($list as $k => &$v) {
			if (!$this->checkType($v, $type, $nullable, $objectType)) {
				throw new InvalidParameterException('Parameter ' . $this->prefixKey($key . '[' . $k . ']') . ' must be '
					. $this->formatTypeLabel($type, $nullable, $objectType) . '.');
			}
		}

		return $list;
	}


	/**
	 * @param mixed $value
	 * @param string $type
	 * @param bool $nullable
	 * @param string|null $objectType
	 * @return bool
	 */
	private function checkType(&$value, $type, $nullable, $objectType)
	{
		if ($nullable && $value === null) {
			return true;
		}

		if ($type === 'string') {
			return is_string($value);
		}

		if ($type === 'int' && $this->isNumericInt($value)) {
			$value = (int) $value;
			return true;
		}

		if ($type === 'float' && $this->isNumeric($value)) {
			$value = (float) $value;
			return true;
		}

		if ($type === 'bool') {
			if (in_array($value, [false, 0, '0', 'false'], true)) {
				$value = false;
				return true;
			}

			if (in_array($value, [true, 1, '1', 'true'], true)) {
				$value = true;
				return true;
			}
		}

		if ($type === 'list') {
			return $this->isList($value);
		}

		if ($type === 'array') {
			return is_array($value);
		}

		if ($type === 'object') {
			return is_object($value) && is_a($value, $objectType);
		}

		if ($type === 'date') {
			if ($value instanceof \DateTime) {
				$value = $value->format('Y-m-d');
			}

			if (is_string($value) && preg_match(self::DATE_REGEXP, $value)) {
				$value = \DateTime::createFromFormat(self::DATETIME_FORMAT, $value . ' 00:00:00');
				return true;
			}
		}

		if ($type === 'datetime') {
			if ($value instanceof \DateTime) {
				$value = $value->format('Y-m-d H:i:s');
			}

			if (is_string($value) && preg_match(self::DATETIME_REGEXP, $value)) {
				$value = \DateTime::createFromFormat(self::DATETIME_FORMAT, $value);
				return true;
			}
		}

		return false;
	}


	/**
	 * This method is a part of the Nette Framework (c) 2004 David Grudl (https://davidgrudl.com), new BSD license
	 * @param mixed $value
	 * @return bool
	 */
	private function isNumericInt($value)
	{
		return is_int($value) || is_string($value) && preg_match('#^-?[0-9]+\z#', $value);
	}


	/**
	 * This method is a part of the Nette Framework (c) 2004 David Grudl (https://davidgrudl.com), new BSD license
	 * @param mixed $value
	 * @return bool
	 */
	private function isNumeric($value)
	{
		return is_float($value) || is_int($value) || is_string($value) && preg_match('#^-?[0-9]*[.]?[0-9]+\z#', $value);
	}


	/**
	 * This method is a part of the Nette Framework (c) 2004 David Grudl (https://davidgrudl.com), new BSD license
	 * @param mixed $value
	 * @return bool
	 */
	private function isList($value)
	{
		return is_array($value) && (!$value || array_keys($value) === range(0, count($value) - 1));
	}


	/**
	 * @param string $key
	 * @return string
	 */
	private function prefixKey($key)
	{
		return ($this->domain !== null ? $this->domain . '.' : '') . $key;
	}


	/**
	 * @param string $type
	 * @param bool $nullable
	 * @param string|null $objectType
	 * @return string
	 */
	private function formatTypeLabel($type, $nullable, $objectType)
	{
		static $labels = [
			'string' => 'a string',
			'int' => 'an integer',
			'float' => 'a float',
			'bool' => 'a boolean',
			'list' => 'a list',
			'array' => 'an array',
			'date' => 'a date',
			'datetime' => 'a datetime',
		];

		if (isset($labels[$type])) {
			$label = $labels[$type];
		} elseif ($type === 'object') {
			$label = 'an instance of ' . $objectType;
		} else {
			$label = $type;
		}

		return $label . ($nullable ? ' or null' : '');
	}
}

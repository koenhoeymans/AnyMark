<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Util;

/**
 * @package AnyMark
 */
class HtmlFileUrlBuilder implements InternalUrlBuilder, FileExtensionProvider
{
	/**
	 * @see AnyMark\Util.InternalUrlBuilder::createLink()
	 */
	public function createRelativeLink($to, $relativeTo = null)
	{
		$numberSignPos = strpos($to, "#");

		if ($numberSignPos === false)
		{
			$filePart = $to;
			$relPart = '';
		}
		else
		{
			$filePart = substr($to, 0, $numberSignPos);
			$relPart = substr($to, $numberSignPos);
		}

		$levelsUp = $this->howManyLevelsDeep($relativeTo);
		while ($levelsUp>0)
		{
			$filePart = '../' . $filePart;
			$levelsUp--;
		}

		$info = pathinfo($filePart);
		if (!isset($info['extension']))
		{
			$filePart = $this->addExtension($filePart);
		}

		return $filePart . $relPart;
	}

	private function howManyLevelsDeep($resource)
	{
		return count(explode(DIRECTORY_SEPARATOR, $resource)) -1;
	}

	/**
	 * Adds '.html' as extension.
	 * 
	 * @param string $resource
	 */
	public function addExtension($resource)
	{
		return $resource . '.html';
	}
}
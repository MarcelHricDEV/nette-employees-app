<?php

declare(strict_types=1);

namespace App\Services;

use DOMDocument;
use DOMNode;


class XMLService
{	
	public function __construct(
		private readonly FilesystemService $filesystemService
	) {
	}

	public function loadFile(string $path): DOMDocument
	{
		$xml = new DOMDocument();
		$xml->formatOutput = true;
		$xml->preserveWhiteSpace = false;
		
		$loaded = $xml->load($this->filesystemService->getStoragePath($path));

		if (!$loaded) {
			throw new \InvalidArgumentException("File [$path] does not exist in storage.");
		}
		
		return $xml;
	}

	/**
	 *
	 * @param DOMNode $root
	 * @return array<string, mixed>|null
	 */
	public function toArray(DOMNode $root): array|null
	{
		return array_values($this->toArrayInternal($root))[0] ?? null;
	}
	
	/**
	 *
	 * @author sweisman@pobox.com
	 * @link https://www.php.net/manual/en/book.dom.php#93717
	 *
	 * @param DOMNode $root
	 * @return array<string, mixed>|string
	 */
	public function toArrayInternal(DOMNode $root): array|string
	{
		$result = [];

		if ($root->hasAttributes()) {
			$attrs = $root->attributes;
			foreach ($attrs as $attr) {
				/* [MODIFIED] Inject attributes directly to main array (not so scalable, but useful for this use case) */
				$result[$attr->name] = $attr->value;
			}
		}

		if ($root->hasChildNodes()) {
			$children = $root->childNodes;
			if ($children->length == 1) {
				$child = $children->item(0);
				if ($child->nodeType === XML_TEXT_NODE) {
					$result['_value'] = $child->nodeValue;
					return count($result) === 1
						? $result['_value']
						: $result;
				}
			}

			/* [MODIFIED]  */
			foreach ($children as $child) {	
				if (count($child->childNodes) === 1) {
					$result[$child->nodeName] = $this->toArrayInternal($child);
				} else {
					$result[$child->nodeName][] = $this->toArrayInternal($child);
				}
			}
		}

		return $result;
	}
}

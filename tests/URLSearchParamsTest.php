<?php

namespace Tests;

use Fabgg\UrlSearchParams\URLSearchParams;
use PHPUnit\Framework\TestCase;

class URLSearchParamsTest extends TestCase
{
    public function testConstructor(): void
    {

        /** construct an empty class */
        $searchParams = new URLSearchParams();
        $this->assertInstanceOf(URLSearchParams::class, $searchParams);
        $this->assertIsArray($searchParams->getAll());
        $this->assertIsString($searchParams->toString());

        /** construct with simple array */
        $searchParams = new URLSearchParams(['q'=> 'hello']);
        $this->assertIsArray($searchParams->get('q'));
        $this->assertSame($searchParams->get('q')[0], 'hello');
        $this->assertSame($searchParams->toString(), '?q=hello');

        /** construct with complex array */
        $searchParams = new URLSearchParams(['q'=> ['hello','bye'],'r'=>'some string']);
        $this->assertIsArray($searchParams->get('q'));
        $this->assertIsArray($searchParams->get('r'));
        $this->assertSame($searchParams->get('q')[0], 'hello');
        $this->assertSame($searchParams->get('q')[1], 'bye');
        $this->assertSame($searchParams->get('r')[0], 'some string');
        $this->assertSame($searchParams->toString(), '?q=hello&q=bye&r=some+string');

        /** construct with simple string */
        $searchParams = new URLSearchParams('?q=hello+world&q=bye');
        $this->assertIsArray($searchParams->get('q'));
        $this->assertSame($searchParams->get('q')[0], 'hello world');
        $this->assertSame($searchParams->get('q')[1], 'bye');

        /** construct with full url */
        $searchParams = new URLSearchParams('https://example.com?q=hello+world&q=bye');
        $this->assertIsArray($searchParams->get('q'));
        $this->assertSame($searchParams->get('q')[0], 'hello world');
        $this->assertSame($searchParams->get('q')[1], 'bye');

        /** string with not rfc character */
        $searchParams = new URLSearchParams('?q=c%C3%A9dric');
        $this->assertSame($searchParams->get('q')[0], 'cédric');
    }

    public function testHas(): void
    {
        $searchParams = new URLSearchParams(['q'=> 'hello']);
        $this->assertTrue($searchParams->has('q'));
        $this->assertFalse($searchParams->has('r'));
    }

    public function testGet(): void
    {
        $searchParams = new URLSearchParams('?q=hello+world&q=bye');
        $this->assertIsArray($searchParams->get('q'));
        $this->assertSame($searchParams->get('q'), ['hello world','bye']);
        $this->assertFalse($searchParams->get('r'));
    }

    public function testGetAll(): void
    {
        $searchParams = new URLSearchParams('?q=hello+world&q=bye&u=12');
        $this->assertIsArray($searchParams->getAll());
        $this->assertSame($searchParams->getAll(), ['q'=>['hello world','bye'],'u'=>['12']]);
        $this->assertIsArray($searchParams->getAll('q'));
        $this->assertSame($searchParams->getAll('q'), ['hello world','bye']);
        $this->assertFalse($searchParams->getAll('r'));
    }

    public function testDelete(): void
    {
        $searchParams = new URLSearchParams('?q=hello+world&q=bye&u=12');
        $this->assertSame($searchParams->getAll(), ['q'=>['hello world','bye'],'u'=>['12']]);
        $searchParams->delete('u');
        $this->assertSame($searchParams->getAll(), ['q'=>['hello world','bye']]);
    }

    public function testAppend(): void
    {
        $searchParams = new URLSearchParams('?q=hello+world&q=bye');
        $searchParams->append(['u' => 12]);
        $this->assertSame($searchParams->getAll(), ['q'=>['hello world','bye'],'u'=>[12]]);
        $searchParams->append(['q'=>'sun']);
        $this->assertSame($searchParams->getAll(), ['q'=>['hello world','bye','sun'],'u'=>[12]]);
    }

    public function testAppendTo(): void
    {
        $searchParams = new URLSearchParams('?q=hello+world&q=bye');
        $searchParams->appendTo('u' , 12);
        $this->assertSame($searchParams->getAll(), ['q'=>['hello world','bye'],'u'=>[12]]);
        $searchParams->appendTo('q','sun');
        $this->assertSame($searchParams->getAll(), ['q'=>['hello world','bye','sun'],'u'=>[12]]);
        $searchParams->appendTo('u' , [12,14]);
        $this->assertSame($searchParams->getAll('u'), [12,14]);
    }

    public function testMerge(): void
    {
        $searchParams = new URLSearchParams('?q=hello');
        $searchParams->merge('?q=goodbye');
        $this->assertSame($searchParams->getAll(), ['q'=>['hello','goodbye']]);
        $searchParams->merge(['q'=>'sun']);
        $this->assertSame($searchParams->getAll(), ['q'=>['hello','goodbye','sun']]);
        $searchParams->merge(['q'=>'hello']);
        $this->assertSame($searchParams->getAll(), ['q'=>['hello','goodbye','sun']]);
        $searchParams->merge(['u'=>'tom']);
        $this->assertSame($searchParams->getAll(), ['q'=>['hello','goodbye','sun'],'u'=> ['tom']]);
        $searchParams->merge(['u'=>'julie']);
        $this->assertSame($searchParams->getAll(), ['q'=>['hello','goodbye','sun'],'u'=> ['tom','julie']]);
        $searchParams->merge('?u=34');
        $this->assertSame($searchParams->getAll(), ['q'=>['hello','goodbye','sun'],'u'=> ['tom','julie','34']]);
    }

    public function testToString(): void
    {
        $searchParams = new URLSearchParams(['q'=>['hello','goodbye','sun'],'u'=> ['tom','julie','34']]);
        $this->assertSame($searchParams->toString(), '?q=hello&q=goodbye&q=sun&u=tom&u=julie&u=34');

        $searchParams = new URLSearchParams(['q'=>['hello'],'u'=> ['cédric']]);
        $this->assertSame($searchParams->toString(), '?q=hello&u=c%C3%A9dric');

        $this->assertSame((string)$searchParams, '?q=hello&u=c%C3%A9dric');

    }
}
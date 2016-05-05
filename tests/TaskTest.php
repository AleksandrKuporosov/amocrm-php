<?php

    class TaskTest extends PHPUnit_Framework_TestCase
    {
        private $model = null;

        public function setUp()
        {
            $paramsBag   = new \AmoCRM\Request\ParamsBag();
            $this->model = new \AmoCRM\Models\Task($paramsBag);
        }

        /**
         * @dataProvider fieldsProvider
         */
        public function testFields($field, $value, $expected)
        {
            $this->model[$field] = $value;

            $this->assertEquals($this->model[$field], $expected);
        }

        public function testApiList()
        {
            $mock = $this->getMockBuilder('\AmoCRM\Models\Task')
                ->setConstructorArgs([new \AmoCRM\Request\ParamsBag()])
                ->setMethods(['apiList'])
                ->getMock();

            $this->assertInstanceOf('\AmoCRM\Models\Task', $mock);

            $mock->expects($this->once())->method('apiList')
                ->with($this->isType('array'))
                ->will($this->returnValue([]));

            $result = $mock->apiList([
                'query' => 'test',
            ]);

            $this->assertEquals([], $result);
        }

        public function testApiAdd()
        {
            $mock = $this->getMockBuilder('\AmoCRM\Models\Task')
                ->setConstructorArgs([new \AmoCRM\Request\ParamsBag()])
                ->setMethods(['apiAdd'])
                ->getMock();

            $this->assertInstanceOf('\AmoCRM\Models\Task', $mock);

            $mock['element_id'] = 11029224;
            $mock['element_type'] = 1;
            $mock['date_create'] = '-2 DAYS';
            $mock['task_type'] = 1;
            $mock['text'] = "Текст\nзадачи";
            $mock['responsible_user_id'] = 798027;
            $mock['complete_till'] = '+1 DAY';

            $mock->expects($this->any())->method('apiAdd')
                ->will($this->returnValueMap([
                    // last arg is return value
                    [[], 100],
                    [[$mock, $mock], [100, 200]],
                ]));

            $this->assertEquals(100, $mock->apiAdd());
            $this->assertCount(2, $mock->apiAdd([$mock, $mock]));
        }

        public function testApiUpdate()
        {
            $mock = $this->getMockBuilder('\AmoCRM\Models\Task')
                ->setConstructorArgs([new \AmoCRM\Request\ParamsBag()])
                ->setMethods(['apiUpdate'])
                ->getMock();

            $this->assertInstanceOf('\AmoCRM\Models\Task', $mock);

            $mock->expects($this->any())->method('apiUpdate')
                ->will($this->returnValue(true));

            $this->assertTrue($mock->apiUpdate(1, 'апдейт'));
            $this->assertTrue($mock->apiUpdate(1, 'апдейт', 'now'));
        }

        public function fieldsProvider()
        {
            return [
                // field, value, expected
                ['element_id', 100, 100],
                ['element_type', 100, 100],
                ['date_create', '2016-04-01 00:00:00', strtotime('2016-04-01 00:00:00')],
                ['last_modified', '2016-04-01 00:00:00', strtotime('2016-04-01 00:00:00')],
                ['request_id', 100, 100],
                ['task_type', 100, 100],
                ['text', "Line 1\nLine 2", "Line 1\nLine 2"],
                ['responsible_user_id', 100, 100],
                ['complete_till', '2016-04-01 00:00:00', strtotime('2016-04-01 00:00:00')],
            ];
        }
    }
<?php
namespace PHPSTORM_META
{
    override(\Psr\Container\ContainerInterface::get(0), map([
        '' => '@',
    ]));
    override(\Onix\Container\ContainerInterface::get(0), map([
        '' => '@',
    ]));
    override(\Onix\Container\Container::get(0), map([
        '' => '@',
    ]));
}

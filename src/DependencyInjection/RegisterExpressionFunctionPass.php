<?php


namespace App\DependencyInjection;


use App\Security\SecurityDisabledChecker;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterExpressionFunctionPass implements CompilerPassInterface
{

    /**
     * This function is here to register the function isSecurityDisabled for security expressions checker.
     */
    public function process(ContainerBuilder $container)
    {
        $container
            ->getDefinition('security.expression_language')
            ->addMethodCall("register", [
                SecurityDisabledChecker::LANGUAGE_EXPRESSION_FUNCTION_NAME,
                [new Reference(SecurityDisabledChecker::class), "check"],
                [new Reference(SecurityDisabledChecker::class), "check"],
            ]);
    }
}
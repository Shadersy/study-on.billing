<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerUmIBiJ4\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerUmIBiJ4/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerUmIBiJ4.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerUmIBiJ4\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerUmIBiJ4\App_KernelDevDebugContainer([
    'container.build_hash' => 'UmIBiJ4',
    'container.build_id' => '0add3194',
    'container.build_time' => 1621754636,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerUmIBiJ4');

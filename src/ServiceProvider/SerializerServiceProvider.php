<?php


namespace AllCoinCore\ServiceProvider;


use Illuminate\Support\ServiceProvider;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SerializerInterface::class, function () {
            $encoders = [new JsonEncoder()];
            $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer(null, null, null, new ReflectionExtractor())];

            return new Serializer($normalizers, $encoders);
        });

        $this->app->singleton(NormalizerInterface::class, function () {
            $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer(null, null, null, new ReflectionExtractor())];

            return new Serializer($normalizers, []);
        });
    }
}

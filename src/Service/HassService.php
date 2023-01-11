<?php

namespace App\Service;

use App\Dto\Entity;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HassService
{

    private Serializer $serializer;

    public function __construct(
        private readonly string              $hassUrl,
        private readonly string              $hassToken,
        private readonly HttpClientInterface $httpClient,
    )
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter())];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function lightToggle(string $lightName) : void {
        $this->query(
            method:'POST',
            endPoint: '/api/services/light/toggle',
            data: [
                'entity_id' => sprintf('light.%s', $lightName),
                'transition' => 0,
            ]
        );
    }

    public function getLightState(string $lightName) : Entity
    {
        $data = $this->query(
            endPoint: sprintf('/api/states/light.%s', $lightName)
        );

        return $this->deserializeEntity($data);
    }

    public function changeBrightnessByStep(int $step, string $lightName) : void {
        $this->query(
            method:'POST',
            endPoint: '/api/services/light/turn_on',
            data: [
                'entity_id' => sprintf('light.%s', $lightName),
                'brightness_step_pct' => $step
            ]
        );
    }

    public function changeBrightnessByValue(int $value, string $lightName) : void {
        $this->query(
            method:'POST',
            endPoint: '/api/services/light/turn_on',
            data: [
                'entity_id' => sprintf('light.%s', $lightName),
                'brightness_pct' => $value
            ]
        );
    }

    private function query(string $method = 'GET', string $endPoint = '/', array $data = []): string
    {
        $q = $this->httpClient->request(
            $method,
            sprintf('%s%s', $this->hassUrl, $endPoint),
            [
                'auth_bearer' => $this->hassToken,
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                $method === 'GET' ? 'query' : 'json' => $data,
            ]
        );

        return $q->getContent();
    }

    private function deserializeEntity(string $data): Entity
    {
        return $this->serializer->deserialize(
            data: $data,
            type: Entity::class,
            format: JsonEncoder::FORMAT
        );
    }

}
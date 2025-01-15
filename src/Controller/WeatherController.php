<?php



namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    private $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    #[Route('/weather', name: 'app_weather')]
    public function index(Request $request): Response
    {
        $city = $request->query->get('city', 'Paris');

        try {
            $weatherData = $this->weatherService->getWeatherData($city);

            if (!$weatherData) {
                throw new \Exception('City not found');
            }

            return $this->render('weather/index.html.twig', [
                'city' => $city,
                'weather' => $weatherData,
            ]);
        } catch (\Exception $e) {
            $this->addFlash('error', 'La ville n\'a pas été trouvée.');
            return $this->render('weather/index.html.twig', [
                'city' => $city,
                'weather' => null,
            ]);
        }
    }
}

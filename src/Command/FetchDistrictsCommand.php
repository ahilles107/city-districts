<?php

namespace App\Command;

use App\Entity\City;
use App\Fetcher\ChainFetcher;
use App\Manager\DistrictManager;
use App\Repository\CityRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class FetchDistrictsCommand extends Command
{

    public const ALL_CITIES = 'all';

    protected static $defaultName = 'app:fetch-districts';

    /**
     * @var ChainFetcher
     */
    protected $districtFetcher;

    /**
     * @var CityRepository
     */
    protected $cityRepository;

    /**
     * @var DistrictManager
     */
    protected $districtManager;

    /**
     * I didn't mapped here params to interfaces to be able to use symfony autowire (as i had limited time for this).
     * At end it's final command without line of configuration - can be easly refactored for changes in future
     */
    public function __construct(ChainFetcher $districtFetcher, CityRepository $cityRepository, DistrictManager $districtManager)
    {
        $this->districtFetcher = $districtFetcher;
        $this->cityRepository = $cityRepository;
        $this->districtManager = $districtManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Fetch city district')
            ->addArgument('city', InputArgument::OPTIONAL, 'City name to fetch')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $city = $this->cityRepository->findOneBy(['name' => $input->getArgument('city')]);

        if (!$city) {
            $io->error(sprintf('%s don\'t exists in system', $input->getArgument('city')));

            return;
        }

        $districts = $this->districtFetcher->fetch($city);
        $this->districtManager->synchronize($city, $districts);

        $io->success(sprintf('Districs for city: %s were fetched.', $city->getName()));
    }
}

<?php

namespace Wi1dcard\LearnkuDeployBot\Commands;

use Wi1dcard\LearnkuDeployBot\Requests\ArticleListRequest;
use Symfony\Component\Console\Input\InputOption;
use Wi1dcard\LearnkuDeployBot\Exceptions\ExtractorException;
use Wi1dcard\LearnkuDeployBot\Extractors\ArticleListExtractor;

final class ArticleListCommand extends LearnkuCommand
{
    protected static $defaultName = 'article:list';

    protected function configure()
    {
        parent::configure();

        $this->setDescription('List all articles')
            ->addOption('page', 'p', InputOption::VALUE_OPTIONAL, 'The page number');
    }

    protected function handle($input, $output)
    {
        $currentPage = $pageNumber = $input->getOption('page');
        $articles = [];

        do {
            $request = new ArticleListRequest($this->cookies, $currentPage);

            $response = $this->httpClient->sendRequest($request);

            $data = (new ArticleListExtractor())->extract($response);

            $articles += $data;
            $currentPage++;
        } while ($pageNumber === null && count($data) !== 0);

        foreach ($articles as $article) {
            $output->writeln($article['id'] . "\t" . $article['title']);
        }
    }
}

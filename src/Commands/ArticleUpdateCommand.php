<?php

namespace Wi1dcard\LearnkuDeployBot\Commands;

use Wi1dcard\LearnkuDeployBot\Requests\ArticleListRequest;
use Symfony\Component\Console\Input\InputOption;
use Wi1dcard\LearnkuDeployBot\Exceptions\ExtractorException;
use Wi1dcard\LearnkuDeployBot\Extractors\ArticleListExtractor;
use Symfony\Component\Console\Input\InputInterface;
use Wi1dcard\LearnkuDeployBot\Requests\SubmitChangesRequest;
use Symfony\Component\Console\Input\InputArgument;

final class ArticleUpdateCommand extends LearnkuCommand
{
    protected static $defaultName = 'article:update';

    protected function configure()
    {
        parent::configure();

        $this->setDescription('Update an article')
            ->addOption('token', 't', InputOption::VALUE_REQUIRED, 'A valid CSRF token')
            ->addOption('id', 'i', InputOption::VALUE_REQUIRED, 'The article ID')
            ->addOption('title', 'l', InputOption::VALUE_REQUIRED, 'The article title')

            ->addArgument('file', InputArgument::REQUIRED, 'Full path of the new article');
    }

    protected function validate(InputInterface $input)
    {
        $id = $input->getOption('id');

        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('Article ID must be numeric: ' . $id);
        }

        $file = $input->getArgument('file');

        if ($file) {
            if (!is_file($file)) {
                throw new \InvalidArgumentException('Article file not exists: ' . $file);
            }
        } elseif (ftell(STDIN) === false) {
            throw new \InvalidArgumentException('Please provide a file or use pipe to STDIN.');
        }
    }

    protected function handle($input, $output)
    {
        $this->validate($input);

        if ($fileName = $input->getArgument('file')) {
            $fileContents = file_get_contents($fileName);
        } else {
            $fileContents = '';
            while (!feof(STDIN)) {
                $fileContents .= fread(STDIN, 1024);
            }
        }

        $request = new SubmitChangesRequest(
            $this->cookies,
            $input->getOption('token'),
            $input->getOption('id'),
            $input->getOption('title'),
            $fileContents
        );

        $response = $this->httpClient->sendRequest($request);

        $statusCode = $response->getStatusCode();

        if ($statusCode != 302) {
            $output->error('Unusual HTTP status code: ' . $statusCode);

            return 1;
        }

        $output->success('Article updated');
    }
}

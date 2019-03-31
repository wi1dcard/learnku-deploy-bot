<?php

namespace Wi1dcard\LearnkuDeployBot\Commands;

use Wi1dcard\LearnkuDeployBot\Requests\ArticleListRequest;
use Symfony\Component\Console\Input\InputOption;
use Wi1dcard\LearnkuDeployBot\Exceptions\ExtractorException;
use Wi1dcard\LearnkuDeployBot\Extractors\ArticleListExtractor;
use Symfony\Component\Console\Input\InputInterface;
use Wi1dcard\LearnkuDeployBot\Requests\SubmitChangesRequest;
use Symfony\Component\Console\Input\InputArgument;
use Wi1dcard\LearnkuDeployBot\Requests\SubmitArticleRequest;
use Symfony\Component\Console\Question\Question;

final class ArticleUpdateCommand extends LearnkuCommand
{
    protected static $defaultName = 'article:update';

    protected function configure()
    {
        parent::configure();

        $this->setDescription('Update an article')
            ->addOption('token', 't', InputOption::VALUE_REQUIRED, 'A valid CSRF token')
            ->addOption('id', 'i', InputOption::VALUE_OPTIONAL, 'The article ID')
            ->addOption('title', 'l', InputOption::VALUE_REQUIRED, 'The article title')
            ->addOption('tag', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The tag(s) that attach to the article')

            ->addArgument('file', InputArgument::OPTIONAL, 'The full path of the article contents');
    }

    protected function validate(InputInterface $input)
    {
        $id = $input->getOption('id');

        if ($id !== '' && $id !== null && !is_numeric($id)) {
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

        $id = $input->getOption('id');
        $token = $input->getOption('token');
        $title = $input->getOption('title');
        $tags = implode(',', $input->getOption('tag'));

        if ($id === '' || $id === null) {
            $answer = $output->askQuestion(
                new Question('Article ID empty, would you like to create a new article [y/N]?')
            );
            switch ($answer) {
                case null:
                case 'n':
                case 'N':
                    return 0;
                case 'y':
                case 'Y':
                    break;
                default:
                    throw new \RuntimeException('Unexpected input.');
            }
            $request = new SubmitArticleRequest($this->cookies, $token, $title, $fileContents, $tags);
        } else {
            $request = new SubmitChangesRequest($this->cookies, $token, $id, $title, $fileContents, $tags);
        }

        $response = $this->httpClient->sendRequest($request);

        $statusCode = $response->getStatusCode();

        if ($statusCode != 302) {
            throw new \RuntimeException('Unusual HTTP status code: ' . $statusCode);
        }

        $output->writeln('Article updated.');
    }
}

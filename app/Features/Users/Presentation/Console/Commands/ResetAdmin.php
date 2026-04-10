<?php

namespace App\Features\Users\Presentation\Console\Commands;

use App\Features\Users\Application\Contracts\ResetAdminUserContract;
use App\Features\Users\Presentation\API\Presenters\ResetAdminUserPresenter;
use Illuminate\Console\Command;

class ResetAdmin extends Command
{
    public function __construct(
        private readonly ResetAdminUserContract $resetAdminUserUsecase,
    ) {
        return parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset admin user to default user/email/password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $presenter = new ResetAdminUserPresenter();
        $this->resetAdminUserUsecase->execute($presenter);
        $this->info($presenter->handle());
    }
}

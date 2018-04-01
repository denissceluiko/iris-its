<?php

namespace App\Console\Commands;

use App\Team;
use Illuminate\Console\Command;

class MattermostCreateTeam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'team:create {team_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registers Mattermost team';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $domain = $this->argument('team_name');

        // Check if there is a not fully unitialized team
        $team = Team::where(['mm_domain' => $domain, 'mm_id' => null])->first();

        if ($team) {
            if ($this->confirm("There already is an unfinished team $domain, would you like to finish it?"))
            {
                $this->call('team:config', ['team_name' => $domain]);
            }
        }
        else
        {
            Team::create(['mm_domain' => $domain]);
            $this->info("Team $domain created.");
            if ($this->confirm('Would you like to add security tokens?'))
            {
                $this->call('team:config', ['team_name' => $domain]);
            }
        }

    }
}

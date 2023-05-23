<?php


namespace App\Repository;


use App\Models\Funnels;

class FunnelRepository
{
    private Funnels $funnelModel;

    public function __construct(Funnels $funnelModel)
    {
        $this->funnelModel = $funnelModel;
    }

    public function getAllFunnels(): array {
        return $this->funnelModel::all()->toArray();
    }

    public function getFunnelById($id): array {
        return $this->funnelModel::where('id',$id)->first()->toArray();
    }
}
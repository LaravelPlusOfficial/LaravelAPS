<?php

namespace App\Models\Traits;


use App\Models\Meta;

trait Metaable
{

    /**
     * Post metas
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function metas()
    {
        return $this->morphMany(Meta::class, 'metaable');
    }

    /**
     * Create or update existing meta
     *
     * @param $metas
     */
    public function createOrUpdateMetas($metas)
    {
        foreach ($metas as $key => $value) {

            $key = str_slug( $key, '_' );

            if ( $meta = $this->checkIfMetaExists( $key ) ) {

                $value ? $meta->update( ['value' => $value] ) : $meta->delete();

            } else {

                $this->metas()->create( [
                    'key'   => $key,
                    'value' => $value
                ] );

            }

        }
    }

    /**
     * Check meta exists for given key and metaable type
     *
     * @param $key
     *
     * @return mixed
     */
    protected function checkIfMetaExists($key)
    {
        return $this->metas()->where( ['key' => $key] )->first();
    }

}
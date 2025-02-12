import { Head, Link, router, usePage } from '@inertiajs/react';
                    import React, { useState } from 'react';
                    import ContentPanel from '../../Components/Table/ContentPanel';
                    const ModuleHeaders = () => {
                        return(
                            <>
                                <ContentPanel>
                                    <div>This is ModuleHeaders module table area</div>
                                </ContentPanel>
                            </>
                        );
                    };

                    export default ModuleHeaders;
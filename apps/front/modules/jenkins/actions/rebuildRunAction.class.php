<?php

/**
 * @method sfPropelRoute getRoute
 */
class rebuildRunAction extends baseJenkinsAction
{

  /**
   * @param sfWebRequest $request
   */
  public function execute($request)
  {
    /** @var JenkinsRun $run */
    $run = $this->getRoute()->getObject();

    if (!$run->hasRequiredParameters($this->getJenkins()))
    {
      $this->getUser()->setFlash('error', sprintf('The build [%s] is not properly parameterized', $run->getJobName()));
    }
    else
    {
      $run->rebuild($this->getJenkins(), $request->getParameter('delayed') == 1);
      $run->computeJobBuildNumber($this->getJenkins(), $this->getUser());

      $this->getUser()->setFlash('info', sprintf('The build [%s] has been relaunched', $run->getJobName()));
    }
    $this->redirect($this->generateUrl('branch_view', $run->getJenkinsGroupRun()));
  }

}
